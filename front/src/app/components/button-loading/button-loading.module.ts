import { CommonModule }             from '@angular/common';
import { ButtonLoadingComponent }   from './button-loading.component';
import { NgModule }                 from '@angular/core';
import { MatProgressSpinnerModule, MatButtonModule } from '@angular/material';

@NgModule({
    declarations: [
        ButtonLoadingComponent
    ],
    imports: [
        CommonModule,
        MatProgressSpinnerModule,
        MatButtonModule
    ],
    exports: [
        ButtonLoadingComponent
    ]
})
export class ButtonLoadingModule { }
