<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class PasswordConstraints
{
    public const MIN_LENGTH = 10;
    public const MAX_LENGTH = 128;
    public const PATTERN = '(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+';
    public const TITLE = 'Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial';

    public static function getHtmlAttributes(): array
    {
        return [
            'minlength' => self::MIN_LENGTH,
            'maxlength' => self::MAX_LENGTH,
            'pattern' => self::PATTERN,
            'title' => self::TITLE,
        ];
    }

    public static function getConstraints(): array
    {
        return [
            new NotBlank(message: 'Veuillez définir un mot de passe'),
            new Length(
                    min: self::MIN_LENGTH,
                    minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                    max: self::MAX_LENGTH,
                    maxMessage: 'Votre mot de passe ne peut pas dépasser {{ limit }} caractères',
                    ),
            new Regex(
                    pattern: '/^' . self::PATTERN . '$/',
                    message: self::TITLE,
                    ),
            new PasswordStrength(
                    message: 'Votre mot de passe est trop faible. Veuillez utiliser un mot de passe plus fort.',
                    ),
            new NotCompromisedPassword(
                    message: 'Ce mot de passe a été exposé lors d\'une fuite de données. Veuillez en choisir un autre.',
                    ),
            ];
    }
}