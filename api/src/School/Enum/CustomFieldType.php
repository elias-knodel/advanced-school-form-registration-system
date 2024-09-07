<?php

namespace App\School\Enum;

enum CustomFieldType: string
{
    const TEXT = 'text';
    const TEXTAREA = 'textarea';
    const SELECT = 'select';
    const CHECKBOX = 'checkbox';
    const RADIO = 'radio';
    const DATE = 'date';
    const TIME = 'time';
    const DATETIME = 'datetime';
    const FILE = 'file';
}
