<?php

declare(strict_types=1);

namespace App\Domain\Responder\Classes;

use App\Domain\Responder\Interfaces\IHttpRedirectResponder;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class WebHttpResponder implements IHttpRedirectResponder
{
    /**
     * @var string
     */
    private string $redirect;

    /**
     * @var array
     */
    private array $data;

    /**
     * @param  string          $view
     * @param  array           $data
     * @return Renderable|View
     */
    public function response(string $view, array $data = []) : Renderable | View
    {
        try {
            return view($view, $data);
        } catch (Throwable $exception) {
            dd($exception);
        }
    }

    /**
     * @param $data
     * @return RedirectResponse
     */
    public function redirect($data) : RedirectResponse
    {
        try {
            $this->setRedirectData($data);
            $redirect = redirect()->to($this->redirect);
            foreach ($this->data as $key => $item) {
                $redirect->with($key, $item);
            }

            return $redirect;
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    /**
     * @param  mixed     $data
     * @throws Exception
     */
    private function setRedirectData(array $data)
    {
        if (empty($data['redirect'])) {
            throw new Exception(trans('dashboard.error.redirect'));
        }
        $this->redirect = $data['redirect'];
        $this->data = ! empty($data['redirect_data']) ? $data['redirect_data'] : [];
    }
}
