<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show($id) {

        $category = Category::find($id);

        if (!$category) {
            $categoryBySlug = Category::where('slug', $id)->first();

            if (!$categoryBySlug) {
                return $this->sendResponse('fail', [], 'Category not found.', 404);
            }    
            return $this->sendResponse('success', [
                'category' => $categoryBySlug,
                'articles' => $categoryBySlug->articles()->paginate(3)
            ], 'Category found successfully.', 200); 
        }

        return $this->sendResponse('success', [
            'category' => $category,
            'articles' => $category->articles()->paginate(3)
        ], 'Category found successfully.', 200);   
    }
}
