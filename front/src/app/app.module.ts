/* Modulos nativos */
import { NgModule }                from '@angular/core';

/* Modulos do template */
import { FuseModule }       from '@fuse/fuse.module';
import { fuseConfig }       from 'app/fuse-config';

import { 
    FuseProgressBarModule, 
    FuseSidebarModule, 
    FuseThemeOptionsModule 
} from '@fuse/components';

/* Components Default */
import { AppComponent } from 'app/app.component';
import { LayoutModule } from 'app/layout/layout.module';

/* Routes */
import { AppRoutingModule } from './app-routing.module';

/* Meus modulos */
import { ErrorsModule } from './pages/errors/errors.module';
import { SharedModule } from './shared.module';

import 'hammerjs';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

/* Interceptors */
import { SessionInterceptor } from './interceptors/session.interceptor';
import { HTTP_INTERCEPTORS }  from '@angular/common/http';

import { registerLocaleData } from '@angular/common';
import localePt               from '@angular/common/locales/pt';
import { LOCALE_ID }          from '@angular/core';

registerLocaleData(localePt, 'pt-BR');

@NgModule({
    declarations: [
        AppComponent,
    ],
    imports     : [
        BrowserAnimationsModule,
        AppRoutingModule,
        FuseModule.forRoot(fuseConfig),
        FuseProgressBarModule,
        FuseSidebarModule,
        FuseThemeOptionsModule,
        LayoutModule,
        ErrorsModule,
        SharedModule
    ],
    bootstrap   : [AppComponent],
    providers: [
        {
            provide: HTTP_INTERCEPTORS,
            useClass: SessionInterceptor,
            multi: true,
        },
        { provide: LOCALE_ID, useValue: 'pt-BR' }
    ]
})
export class AppModule
{
}

