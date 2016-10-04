import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute	} from '@angular/router';
import { NgForm } from '@angular/forms';

import { SlimLoadingBarService } from 'ng2-slim-loading-bar';

import { DataService } from '../shared/services/data.service';
import { ItemsService } from '../shared/utils/items.service';
import { NotificationService } from '../shared/utils/notification.service';
import { ConfigService } from '../shared/utils/config.service';
import { MappingService } from '../shared/utils/mapping.service';
import { IUser, IUserDetails, IDataInitial } from '../shared/interfaces';
import { DateFormatPipe } from '../shared/pipes/date-format.pipe';

@Component({
	moduleId: module.id,
    selector: 'app-user-add',
    templateUrl: 'user-add.component.html'
})
export class UserAddComponent implements OnInit {
    apiHost: string;
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
        this.apiHost = this.configService.getApiHost();
        this.loadDataInitialForAdd();
    }

    loadDataInitialForAdd() {
        this.loadingBarService.start();
        this.dataService.getDataInitial()
            .subscribe((data: IDataInitial) => {
				this.user = this.itemsService.getSerialized<IUserDetails>(data);				
                this.userLoaded = true;
                // Convert date times to readable format
                this.user.created_at = new Date();                 
                this.statuses = this.user.statuses;
				console.info(this.user);
				
				this.loadingBarService.complete();
            },
            error => {
                this.loadingBarService.complete();
                this.notificationService.printErrorMessage('Failed to load user. ' + error);
            });
    }

    registerUser(addUserForm: NgForm) {
        console.log(addUserForm.value);

        var dataUserMapped = this.mappingService.mapUserDetailsToUser(this.user);

        this.loadingBarService.start();
        this.dataService.registerUser(dataUserMapped)
            .subscribe(() => {
                this.notificationService.printSuccessMessage('User has been registered');
                this.loadingBarService.complete();
				this.router.navigate(['/users']);
            },
            error => {
                this.loadingBarService.complete();
                this.notificationService.printErrorMessage('Failed to register user. ' + error);
            });
    }

    

    back() {
        this.router.navigate(['/users']);
    }

}