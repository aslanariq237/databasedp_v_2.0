<?php

function toastError(string $message)
{
    session()->flash('toast_error', $message);
}

function toastSuccess(string $message)
{
    session()->flash('toast_success', $message);
}