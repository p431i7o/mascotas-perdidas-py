<?php

namespace App\Repositories;

interface PermissionInterface
{
    // referenciales
    const VIEW_USERS = 'Ver usuarios';
    const VIEW_ROLES = 'Ver roles';

    // referenciales
    const CREATE_USERS = 'Crear usuarios';
    const CREATE_ROLES = 'Crear roles';

    // referenciales
    const EDIT_USERS = 'Editar usuarios';
    const EDIT_ROLES = 'Editar roles';

    // referenciales
    const DESTROY_USERS = 'Borrar usuarios';
    const DESTROY_ROLES = 'Borrar roles';
}

class Permissions implements PermissionInterface
{
    /**
     * get all permissions
     *
     * @return array
     */
    public static function all()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return array_values($reflection->getConstants());
    }
}