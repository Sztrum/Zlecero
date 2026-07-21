<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\UI\Http\Controllers;

use App\V1\Core\UI\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class FrontStaticPageController extends Controller
{
    public function home(): View
    {
        return view('static-pages::home', [
            'meta' => __('static-pages::home.meta'),
            'navigationItems' => __('static-pages::home.navigation'),
            'heroStats' => __('static-pages::home.hero.stats'),
            'problemItems' => __('static-pages::home.problems.items'),
            'processSteps' => __('static-pages::home.process.steps'),
            'currentWorkflow' => __('static-pages::home.comparison.current.items'),
            'zleceroWorkflow' => __('static-pages::home.comparison.zlecero.items'),
            'pilotFields' => __('static-pages::home.pilot.fields'),
            'faqItems' => __('static-pages::home.faq.items'),
            'footerColumns' => __('static-pages::home.footer.columns'),
        ]);
    }
}
