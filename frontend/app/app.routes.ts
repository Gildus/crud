import { ModuleWithProviders }  from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HomeComponent } from './home/home.component';
import { UserListComponent } from './user/user-list.component';
import { UserEditComponent } from './user/user-edit.component';
import { UserAddComponent } from './user/user-add.component';

const appRoutes: Routes = [
    { path: 'users', component: UserListComponent },
    { path: 'users/add', component: UserAddComponent },
    { path: 'users/:id/edit', component: UserEditComponent },
    { path: '', component: HomeComponent }
];

export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);