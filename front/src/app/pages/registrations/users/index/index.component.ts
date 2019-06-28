import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { Router }                               from '@angular/router';

import { fuseAnimations } from '@fuse/animations';
import { UsersService }   from 'app/services/users.service';

/* Types */
import { User } from 'app/types/user';

/* Material */
import { MatDialog, MatDialogRef, MatSnackBar } from '@angular/material';

/* Fuse components */
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

/* Filtro */
import { UserFiltersComponent } from './filters/user-filters.component';

/* Alterar senha */
import { UserPasswordComponent } from './password/user-password.component';

@Component({
    templateUrl  : './index.component.html',
    styleUrls    : ['./index.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations   : fuseAnimations
})
export class IndexComponent implements OnInit
{

    displayedColumns: Array<string> = ['name', 'email', 'created_at', 'updated_at', 'deleted_at', 'buttons'];
    users: Array<User> = [];
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;
    usedFilters: any;

    /**
     * Flag para indicar que os dados estão sendo buscados
     */
    searching: boolean = true;

    constructor(private usersService: UsersService,
                private _matDialog: MatDialog,
                private _matSnackBar: MatSnackBar,
                private router: Router) {}

    async ngOnInit() {

        /* Busca os usuários */
        const result = await this.usersService.get('users');
        if (result.code == 200)
            this.users = result.users;

        this.searching = false;
    }
    
    /**
     * Navega para a pagina de insert/update de usuarios
     * @param user 
     */
    edit(user: User) : void {
        this.router.navigate([`registrations/users/${user.id}/save`]);
    }

    /**
     * Pega a imagem do usuário no servidor ou uma default se não houver
     * @param image 
     */
    getImage(image: any) : string {

        if (image)
            return this.usersService.getApi() + 'images/users/' + image;
        else
            return 'assets/images/avatars/profile.jpg';
        
    }
    
    /**
     * Altera o status do usuário
     * @param user 
     */
    changeStatus(user: User) : void {

        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        /* Altera a mensagem padrão do component */
        this.confirmDialogRef.componentInstance.confirmMessage = 'Tem certeza que deseja alterar o status do usuário?';

        /* Depois da modal fechar, captura o resultado e realiza uma determianda ação */
        this.confirmDialogRef.afterClosed().subscribe(async (result) => {
            
            if (result) {

                let endpoint = `users/${user.id}`;
                let res      = null;
                if (user.deleted_at) {
                    endpoint += '/restore';
                    res   = await this.usersService.put({}, endpoint)
                } else
                    res   = await this.usersService.delete(endpoint);

                if (res.code == 200)
                    user.deleted_at = user.deleted_at ? null : 'true';

                /* Exibe uma mensagem de sucesso ou erro */
                this._matSnackBar.open(res.msg, res.code == 200 ? 'Sucesso' : 'Erro', {
                    verticalPosition: 'top',
                    duration        : 2000
                });

            }
            
            this.confirmDialogRef = null;
        });

        
    }

    changePassword(id: number) {

        const modalPassword = this._matDialog.open(UserPasswordComponent, {
            width: '25%',
            data: {id}
        });

    }

    filter() {

        /* Abre a modal */
        const filter = this._matDialog.open(UserFiltersComponent, {
            width: '80%',
            data: {filters: this.usedFilters}
        });

        /* Captura os resultados da modal após o fechamento */
        filter.afterClosed().subscribe(res => {

            this.usedFilters = res.filters;

            if (res && res.data.code == 200)
                this.users = res.data.users;
            
        });
    }
    
}
