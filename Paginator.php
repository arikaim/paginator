<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Core\Paginator;

use Arikaim\Core\Paginator\PaginatorInterface;

/**
 * Paginator base class
*/
class Paginator implements PaginatorInterface 
{  
    const UNKNOWN = -1;   
    const DEFAULT_PER_PAGE = 25;

    const CARD_VIEW  = 'card';
    const TABLE_VIEW = 'table';
    const GRID_VIEW  = 'grid';

    /**
     * Paginator items
     *
     * @var array|mixed
    */
    protected $items;

    /**
     * Current page
     *
     * @var integer
    */
    protected $currentPage;

    /**
     * Last page
     *
     * @var integer
     */
    protected $lastPage;

    /**
     * Row per page value
     *
     * @var integer
     */
    protected $perPage = Self::DEFAULT_PER_PAGE;

    /**
     * Total number of items before slice
     *
     * @var integer
     */
    protected $total;

    /**
     * Constructor
     */
    public function __construct(
        int $currentPage = 1, 
        $items = [], 
        int $perPage = Self::DEFAULT_PER_PAGE, 
        ?int $lastPage = 1,
        int $total = 1
    )
    {
        $this->currentPage = ($currentPage == 0) ? 1 : $currentPage;       
        $this->items = $items;     
        $this->perPage = $perPage;
        $this->total = $total;
        $this->lastPage = $lastPage ?? $this->calcLastPage();
    }    

    /**
     * Return items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get current page
     *
     * @return integer
     */
    public function getCurrentPage(): int
    {
        if (empty($this->currentPage) == true) {
            return 1;   
        }
        if ($this->lastPage != Self::UNKNOWN) {
            return ($this->currentPage > $this->lastPage) ? $this->lastPage : $this->currentPage;
        }

        return $this->currentPage;
    }

    /**
     * Get first item
     *
     * @return mixed
     */
    public function getFirstItem()
    {
        return $this->items[0] ?? null;
    }

    /**
     * Get total items
     *
     * @return integer
     */
    public function getTotalItems(): int
    {
        return (empty($this->total) == true) ? 0 : $this->total;
    }

    /**
     * Get last item
     *
     * @return mixed
     */
    public function getLastItem()
    {
        return (\is_array($this->items) == true) ? \end($this->items) : Self::UNKNOWN;
    }

    /**
     * Get last page
     *
     * @return integer
     */
    public function getLastPage(): int
    {        
        return $this->lastPage;
    }

    /**
     * Get rows per page
     *
     * @return integer
     */
    public function getPerPage(): int
    {
        return (empty($this->perPage) == true) ? Self::DEFAULT_PER_PAGE : $this->perPage;
    }

    /**
     * Return items count
     *
     * @return integer
     */
    public function getItemsCount(): int
    {
        return \count($this->items);
    }

    /**
     * Convert paginator data to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'paginator' => $this->getPaginatorData(),
            'rows'      => $this->getItems()        
        ];
    }

    /**
     * Get paginator data
     *
     * @return array
     */
    public function getPaginatorData(): array
    {
        return [
            'current_page' => $this->getCurrentPage(),            
            'last_page'    => $this->lastPage,          
            'per_page'     => $this->getPerPage(),                
            'total'        => $this->getTotalItems()                         
        ];
    }

    /**
     * Slice array items
     *
     * @param array $items
     * @return array
     */
    protected function sliceItems(array $items)
    {    
        $offset = ($this->currentPage - 1) * $this->getPerPage();

        return \array_slice($items,$offset,$this->getPerPage());      
    }

    /**
     * Calc last page
     *
     * @return integer
     */
    protected function calcLastPage(): int
    {
        return \max((int)\ceil($this->total / $this->perPage),1);
    }
}
