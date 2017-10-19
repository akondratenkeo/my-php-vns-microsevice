<?php

trait SendTrait
{
    protected function prepareResponseForAjax(array $contents)
    {
        $error = array_key_exists('error', $contents) ? $contents['error'] : null;

        return [
            'status' => $error ? 'FAIL' : 'OK',
            'message' => (!$error && array_key_exists('message', $contents)) ? $contents['message'] : $error,
            'data' => (!$error && array_key_exists('data', $contents)) ? $contents['data'] : null,
        ];
    }
}
