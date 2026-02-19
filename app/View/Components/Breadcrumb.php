<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(private array $segments = [])
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Closure|string|View
     */
    public function render() : View | string | Closure
    {
        return view('components.breadcrumb');
    }

    /**
     * @return string
     */
    public function segments() : string
    {
        $breadCrumbSegments = '';
        if (is_array($this->segments) && ! empty($this->segments)) {
            foreach ($this->segments as $segment => $url) {
                $breadCrumbSegments .= view('components.breadcrumb-segment', compact('url', 'segment'))->render();
            }
        }

        return $breadCrumbSegments;
    }
}
