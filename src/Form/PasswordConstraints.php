<?php

namespace App\Form;

class PasswordConstraints
{
    public const MIN_LENGTH = 10;
    public const PATTERN = '(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+';
    public const TITLE = 'Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial';

    public static function getHtmlAttributes(): array
    {
        return [
            'minlength' => self::MIN_LENGTH,
            'pattern' => self::PATTERN,
            'title' => self::TITLE,
        ];
    }
}