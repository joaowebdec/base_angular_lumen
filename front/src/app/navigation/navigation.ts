import { FuseNavigation } from '@fuse/types';

export const navigation: FuseNavigation[] = [
    {
        id       : 'dashboard',
        title    : 'Dashboard',
        type     : 'item',
        url      : '/dashboard',
        icon     : 'home',
        first    : true
    },
    {
        id       : 'cadastros',
        title    : 'Cadastros',
        type     : 'group',
        children : [
            {
                id       : 'usuarios',
                title    : 'Usuarios',
                type     : 'item',
                icon     : 'people',
                url      : '/registrations/users'
            }
        ]
    }
];
