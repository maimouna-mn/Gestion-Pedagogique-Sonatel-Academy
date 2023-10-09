<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function all() {
      return Module::with(('professeurs'))->get();
    }
}
