<?php


namespace App\Twig;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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
        return $this->categoryRepository->getCategoriesLimit();
    }
}
