<?php                      // je crée cette class search afin de représenter mes recherches sous forme d'objet (le filtre par produit ou par catégorie)

namespace App\Classe;  

use App\Entity\Category;

class Search
{
    /**
     * @var string
     */
    
    public $string = '';



    /**
    * @var Category[]
     */

    public $categories = [];
}





