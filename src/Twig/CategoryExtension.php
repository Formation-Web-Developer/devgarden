<?php


namespace App\Twig;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ResourceRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    private CategoryRepository $categoryRepository;
    private ResourceRepository $resourceRepository;

    public function __construct(CategoryRepository $categoryRepository, ResourceRepository $resourceRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->resourceRepository = $resourceRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getCategories', [$this,'categories'])
        ];
    }

    /**
     * @return Category[]
     */
    public function categories(): array
    {
        return $this->categoryRepository->getCategoriesLimit($this->resourceRepository);
    }
}
