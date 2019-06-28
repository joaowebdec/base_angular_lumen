import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

/* Pages */
import { LoginComponent }    from './layout/login/login.component';
import { DefaultComponent }  from './layout/default/default.component';
import { NotFoundComponent } from './pages/errors/notfound/notfound.component';

/* Guards */
import { AuthGuard } from './guards/AuthGuard';

const routes: Routes = [

	{ path: '', component: LoginComponent },
	{ path: '', component: DefaultComponent, children: 
		[
			{ path: 'dashboard', loadChildren: './pages/dashboard/dashboard.module#DashboardModule', canActivate: [ AuthGuard ] },
			{ path: 'registrations', loadChildren: './pages/registrations/cadastros.module#CadastrosModule', canActivate: [ AuthGuard ] }
		]
	},
	{ path: '**', component: NotFoundComponent }

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
