<?php
function redirect_to(string $url): void
{
    header('Location:' . $url);
    exit;
}

function redirect_with(string $url, array $items): void
{
    foreach ($items as $key => $value) {
        $_SESSION[$key] = $value;
    }

    redirect_to($url);
}

const DEFAULT_VALIDATION_ERRORS = [
    'alphanumeric' => 'The %s does not allow any special symbols',
    'same' => 'The %s must match',
    'email' => 'The %s is not valid',
    'between' => 'The %s must have between %d and %d characters',
    'max' => 'The %s must have at most %s characters',
    'min' => 'The %s must have at least %s characters',
    'unique' => 'The %s is has already been taken',
    'secure' => 'The %s must be more secure',
    'required' => 'The %s is required',
];

function filter(array $data, array $fields): array
{
    $validation = [];

    foreach ($fields as $field => $rules) {
        $validation[$field] = $rules;
    }
    $errors = validate($data, $validation);

    return [$data, $errors];
}
function validate(array $data, array $fields): array
{
    $splitFn = fn($str, $separator) => array_map('trim', explode($separator, $str));

    $validation_errors = DEFAULT_VALIDATION_ERRORS;

    $errors = [];

    foreach ($fields as $field => $option) {

        $rules = $splitFn($option, '|');

        foreach ($rules as $rule) {
            $params = [];

            if (strpos($rule, ':')) {
                [$rule_name, $param_str] = $splitFn($rule, ':');
                $params = $splitFn($param_str, ',');
            } else {
                $rule_name = trim($rule);
            }
            $fn = 'is_' . $rule_name;

            if (is_callable($fn)) {
                $pass = $fn($data[$field], ...$params);
                if (!$pass) {
                    $errors[$field] = sprintf(
                        $validation_errors[$rule_name],
                        $field,
                        ...$params
                    );
                }
            }
        }
    }

    return $errors;
}

//validator functions
function is_required(string $data): bool
{
    return isset($data) && trim($data) !== '';
}

function is_email(string $data): bool
{
    if (empty($data)) {
        return true;
    }

    return filter_var($data, FILTER_VALIDATE_EMAIL);
}

function is_min(string $data, int $min): bool
{
    if (!isset($data)) {
        return true;
    }

    return mb_strlen($data) >= $min;
}

function is_max(string $data, int $max): bool
{
    if (!isset($data)) {
        return true;
    }

    return mb_strlen($data) <= $max;
}

function is_between(string $data, int $min, int $max): bool
{
    if (!isset($data)) {
        return true;
    }

    $len = mb_strlen($data);
    return $len >= $min && $len <= $max;
}

function is_same(string $data, string $other): bool
{
    //to do check if the passwords are the same
    if(isset($data)){
        return true;
    }

    return false;
}

function is_alphanumeric(string $data,): bool
{
    if (!isset($data)) {
        return true;
    }

    return ctype_alnum($data);
}

function is_secure(string $data): bool
{
    if (!isset($data)) {
        return false;
    }

    $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
    return preg_match($pattern, $data);
}

//to do
//a function that checks for non unique data 
//to throw an error if that happens while filtering
