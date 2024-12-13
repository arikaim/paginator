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

use Illuminate\Database\Eloquent\Builder;

use Arikaim\Core\Paginator\PaginatorInterface;
use Arikaim\Core\Collection\Interfaces\CollectionInterface;

use Arikaim\Core\Paginator\Paginator;
use Arikaim\Core\Paginator\ArrayPaginator;
use Arikaim\Core\Paginator\JsonPaginator;
use Arikaim\Core\Paginator\DbPaginator;
use Arikaim\Core\Paginator\FeedsPaginator;
use Arikaim\Core\Collection\FeedCollection;
use Arikaim\Core\Utils\Utils;

/**
 * Paginator factory class
*/
class PaginatorFactory 
{  
    /**
     * Create paginator
     *
     * @param object|array|string $source   
     * @param integer $page
     * @param integer|null $perPage                         
     * @return PaginatorInterface
     */
    public static function create($source, int $page = 1, int $perPage = 25): PaginatorInterface
    {       
        if ($source === null || empty($source) == true) {
            return new Paginator();
        };
        
        switch($source) {
            case ($source instanceof Builder): {                        
                $paginator = new DbPaginator($source,$page,$perPage);
                break;
            }
            case ($source instanceof FeedCollection): {                        
                $paginator = new FeedsPaginator($source,$page,$perPage);
                break;
            }      
            case ($source instanceof CollectionInterface): {                        
                $paginator = new ArrayPaginator($source->toArray(),$page,$perPage);
                break;
            }                             
            case \is_array($source): {
                $paginator = new ArrayPaginator($source,$page,$perPage);
                break;
            }
            case Utils::isJson($source): {
                $paginator = new JsonPaginator($source,$page,$perPage);
                break;
            }
            default: {
                $paginator = new Paginator();
            }
        }
        
        return $paginator;
    }
}
