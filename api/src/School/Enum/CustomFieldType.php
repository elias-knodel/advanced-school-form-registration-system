<?php

namespace App\School\Enum;

enum CustomFieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case CHECKBOX = 'checkbox';
    case RADIO = 'radio';
    case DATE = 'date';
    case DATETIME = 'datetime';
    case FILE = 'file';
}
