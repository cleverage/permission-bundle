CleverAge/PermissionBundle Documentation
==================================

This bundle allows you to define role-based permissions for any PHP class. The classic use-case is for Doctrine
entities.

## Quick example

Roles are just meant as an example, there is no hard-coded role in this bundle.

````yaml
clever_age_permission:
    classes:
        App\Entity\Article:
            permissions:
                # list: ~ # Don't define a permission: means granted for all
                create: [] # Defined but left empty: deny access for all
                edit: [ROLE_SUPER_ADMIN] # Only allows super admins to edit
                delete: [ROLE_DATA_ADMIN]
````
