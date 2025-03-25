<?php
    namespace App\Enums;

    enum ExportErrorType: int {
        case None = 0;
        case EmptySelection = 1;
        case InvalidSelection = 2;
        case ExceedLimit = 3;
    }
