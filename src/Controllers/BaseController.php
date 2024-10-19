<?php

namespace PressbooksAudioExport\Controllers;

use Pressbooks\Container;

class BaseController
{
    protected mixed $view;

    public function __construct()
    {
        $this->view = Container::get('Blade');
    }

    protected function renderView(string $view, array $data = []): string
    {
        return $this->view->render(
            "PressbooksAudioExport::{$view}",
            $data
        );
    }

    public function prepareFields(array $data, array $rules): array
    {
        $validated = [];

        foreach ($rules as $field => $rule) {
            if ($rule === 'boolean') {
                $validated[$field] = (bool) ($data[$field] ?? false);

                continue;
            }

            $validated[$field] = sanitize_text_field($data[$field] ?? '');
        }

        return $validated;
    }
}
