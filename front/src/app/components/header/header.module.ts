import { CommonModule }                   from '@angular/common';
import { HeaderSaveComponent }            from './save/header-save.component';
import { HeaderIndexComponent }           from './index/header-index.component';
import { NgModule }                       from '@angular/core';
import { MatIconModule, MatButtonModule, MatMenuModule, MatProgressSpinnerModule } from '@angular/material';
import { RouterModule }                   from '@angular/router';
import { FlexLayoutModule }               from '@angular/flex-layout';

@NgModule({
    declarations: [
        HeaderSaveComponent,
        HeaderIndexComponent
    ],
    imports: [
        CommonModule,
        MatIconModule,
        MatButtonModule,
        RouterModule,
        FlexLayoutModule,
        MatMenuModule,
        MatProgressSpinnerModule
    ],
    exports: [
        HeaderSaveComponent,
        HeaderIndexComponent
    ]
})
export class HeaderModule { }
