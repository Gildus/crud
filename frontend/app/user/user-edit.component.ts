import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute	} from '@angular/router';
import { NgForm } from '@angular/forms';

import { SlimLoadingBarService } from 'ng2-slim-loading-bar';

import { DataService } from '../shared/services/data.service';
import { ItemsService } from '../shared/utils/items.service';
import { NotificationService } from '../shared/utils/notification.service';
import { ConfigService } from '../shared/utils/config.service';
import { MappingService } from '../shared/utils/mapping.service';
import { IUser, IUserDetails } from '../shared/interfaces';
import { DateFormatPipe } from '../shared/pipes/date-format.pipe';

@Component({
    moduleId: module.id,
    selector: 'app-user-edit',
    templateUrl: 'user-edit.component.html'
})
export class UserEditComponent implements OnInit {
    apiHost: string;
    id: number;
    user: IUserDetails;
    userLoaded: boolean = false;
    statuses: string[];
    types: string[];
    private sub: any;

    constructor(private route: ActivatedRoute,
        private router: Router,
        private dataService: DataService,
        private itemsService: ItemsService,
        private notificationService: NotificationService,
        private configService: ConfigService,
        private mappingService: MappingService,
        private loadingBarService:SlimLoadingBarService) { }

    ngOnInit() {
        // (+) converts string 'id' to a number
	    this.id = +this.route.snapshot.params['id'];
        this.apiHost = this.configService.getApiHost();
        this.loadUserDetails();
    }

    loadUserDetails() {
        this.loadingBarService.start();
        this.dataService.getUserDetails(this.id)
            .subscribe((responseDataUser: IUserDetails) => {
                this.user = this.itemsService.getSerialized<IUserDetails>(responseDataUser);
                console.log(this.user);
				this.userLoaded = true;
                // Convert date times to readable format
                this.user.created_at = new Date(this.user.created_at.toString());                
                this.statuses = this.user.statuses;
                this.loadingBarService.complete();
            },
            error => {
                this.loadingBarService.complete();
                this.notificationService.printErrorMessage('Failed to load user. ' + error);
            });
    }

    updateUser(editUserForm: NgForm) {
        console.log(editUserForm.value);

        var userMapped = this.mappingService.mapUserDetailsToUser(this.user);

        this.loadingBarService.start();
        this.dataService.updateUser(userMapped)
            .subscribe(() => {
                this.notificationService.printSuccessMessage('User has been updated');
                this.loadingBarService.complete();
				this.router.navigate(['/users']);
            },
            error => {
                this.loadingBarService.complete();
                this.notificationService.printErrorMessage('Failed to update user. ' + error);
            });
    }
    

    back() {
        this.router.navigate(['/users']);
    }

}