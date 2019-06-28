import { NgModule }                                  from '@angular/core';
import { DefaultComponent }                          from './default/default.component';
import { LoginComponent }                            from './login/login.component';
import { FuseSidebarModule, FuseThemeOptionsModule } from '@fuse/components';
import { FooterModule }                              from 'app/layout/components/footer/footer.module';
import { NavbarModule }                              from 'app/layout/components/navbar/navbar.module';
import { ToolbarModule }                             from 'app/layout/components/toolbar/toolbar.module';
import { SharedModule }                              from 'app/shared.module';
import { ButtonLoadingModule }                       from 'app/components/button-loading/button-loading.module';


@NgModule({
    declarations: [
        DefaultComponent,
        LoginComponent,
    ],
    imports: [
        FuseSidebarModule,
        FuseThemeOptionsModule,
        FooterModule,
        NavbarModule,
        ToolbarModule,
        SharedModule,
        ButtonLoadingModule
    ],
    exports: [
    ]
})
export class LayoutModule
{
}
