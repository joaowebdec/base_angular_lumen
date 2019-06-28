import { CommonModule }                   from '@angular/common';
import { LoadingPulseInputComponent }     from './loading-pulse-input/loading-pulse-input.component';
import { LoadingPulseProfileComponent }   from './loading-pulse-profile/loading-pulse-avatar.component';
import { LoadingPulseTableComponent }     from './loading-pulse-table/loading-pulse-table.component';
import { NgModule }                       from '@angular/core';
import { FlexLayoutModule }               from '@angular/flex-layout';

@NgModule({
    declarations: [
        LoadingPulseInputComponent,
        LoadingPulseProfileComponent,
        LoadingPulseTableComponent
    ],
    imports: [
        CommonModule,
        FlexLayoutModule
    ],
    exports: [
        LoadingPulseInputComponent,
        LoadingPulseProfileComponent,
        LoadingPulseTableComponent
    ]
})
export class LoadingPulseModule { }
