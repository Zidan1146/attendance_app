<?php
    namespace App\Enums;

    enum Permission: string {
        case SuperAdmin = "superadmin";
        case Admin = "admin";
        case User = "user";
    }
