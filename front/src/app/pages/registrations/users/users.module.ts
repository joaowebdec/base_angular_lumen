import { CommonModule } from '@angular/common';
import { NgModule }     from '@angular/core';

import { UsersRoutingModule }    from './users-routing.module';
import { IndexComponent }        from './index/index.component';
import { SaveComponent }         from './save/save.component';
import { UserFiltersComponent }  from './index/filters/user-filters.component';
import { UserPasswordComponent } from './index/password/user-password.component';

/* Meus modulos */
import { SharedModule }        from 'app/shared.module';
import { ButtonLoadingModule } from 'app/components/button-loading/button-loading.module';
import { UploadFileModule }    from 'app/components/upload-file/upload-file.module';

/* Fuse components */
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

/* Mask */
import { NgxMaskModule } from 'ngx-mask';

@NgModule({
    declarations: [
        IndexComponent,
        SaveComponent,
        FuseConfirmDialogComponent,
        UserFiltersComponent,
        UserPasswordComponent
    ],
    imports: [
        CommonModule,
        UsersRoutingModule,
        SharedModule,
        ButtonLoadingModule,
        UploadFileModule,
        NgxMaskModule.forRoot()
    ],
    providers: [],
    entryComponents: [
        FuseConfirmDialogComponent,
        UserFiltersComponent,
        UserPasswordComponent
    ]
})
export class UsersModule { }
