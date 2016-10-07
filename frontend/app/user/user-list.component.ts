import { Component, OnInit, ViewChild, Input, Output,
    trigger,
    state,
    style,
    animate,
    transition } from '@angular/core';

//import {ModalDirective } from 'ng2-bootstrap';
import { ModalDirective } from 'ng2-bootstrap/components/modal/modal.component';
import { SlimLoadingBarService } from 'ng2-slim-loading-bar';

import { DataService } from '../shared/services/data.service';
import { DateFormatPipe } from '../shared/pipes/date-format.pipe';
import { ItemsService } from '../shared/utils/items.service';
import { NotificationService } from '../shared/utils/notification.service';
import { ConfigService } from '../shared/utils/config.service';
import { IUser, IUserDetails, Pagination, PaginatedResult } from '../shared/interfaces';

@Component({
    moduleId: module.id,
    selector: 'app-users',
    templateUrl: 'user-list.component.html',
    animations: [
        trigger('flyInOut', [
            state('in', style({ opacity: 1, transform: 'translateX(0)' })),
            transition('void => *', [
                style({
                    opacity: 0,
                    transform: 'translateX(-100%)'
                }),
                animate('0.5s ease-in')
            ]),
            transition('* => void', [
                animate('0.2s 10 ease-out', style({
                    opacity: 0,
                    transform: 'translateX(100%)'
                }))
            ])
        ])
    ]
})
export class UserListComponent implements OnInit {
    @ViewChild('childModal') public childModal: ModalDirective;
    users: IUser[];
    apiHost: string;

    public itemsPerPage: number = 3;
    public totalItems: number = 0;
    public currentPage: number = 1;

    // Modal properties
    @ViewChild('modal')
    modal: any;
    items: string[] = ['item1', 'item2', 'item3'];
    selected: string;
    output: string;
    selectedUserId: number;
    userDetails: IUserDetails;
    selectedUserLoaded: boolean = false;
    index: number = 0;
    backdropOptions = [true, false, 'static'];
    animation: boolean = true;
    keyboard: boolean = true;
    backdrop: string | boolean = true;

    constructor(
        private dataService: DataService,
        private itemsService: ItemsService,
        private notificationService: NotificationService,
        private configService: ConfigService,
        private loadingBarService:SlimLoadingBarService) { }

    ngOnInit() {
        this.apiHost = this.configService.getApiHost();
        this.loadUsers();
    }

    loadUsers() {
        this.loadingBarService.start();

        this.dataService.getUsers(this.currentPage, this.itemsPerPage)
            .subscribe((res: PaginatedResult<IUser[]>) => {
                this.users = res.result;
                this.totalItems = res.pagination.TotalItems;
                this.loadingBarService.complete();
            },
            error => {
                this.loadingBarService.complete();
                this.notificationService.printErrorMessage('Failed to load users. ' + error);
            });
    }

    pageChanged(event: any): void {
        this.currentPage = event.page;
        this.loadUsers();
        //console.log('Page changed to: ' + event.page);
        //console.log('Number items per page: ' + event.itemsPerPage);
    };

    removeUser(params: IUser) {
        this.notificationService.openConfirmationDialog('Are you sure you want to delete this item ?',
            () => {
                this.loadingBarService.start();
                this.dataService.deleteUser(params.id)
                    .subscribe(() => {
                        this.itemsService.removeItemFromArray<IUser>(this.users, params);
                        this.notificationService.printSuccessMessage(params.names + ' has been deleted.');
                        this.loadingBarService.complete();
                    },
                    error => {
                        this.loadingBarService.complete();
                        this.notificationService.printErrorMessage('Failed to delete ' + params.names + ' ' + error);
                    });
            });
    }

    viewUserDetails(id: number) {
        this.selectedUserId = id;

        this.dataService.getUserDetails(this.selectedUserId)
            .subscribe((responseUser: IUserDetails) => {
                this.userDetails = this.itemsService.getSerialized<IUserDetails>(responseUser);
                // Convert date times to readable format
                this.userDetails.created_at = new DateFormatPipe().transform(responseUser.created_at, ['local']);        
                this.loadingBarService.complete();
                this.selectedUserLoaded = true;
                this.childModal.show();//.open('lg');
            },
            error => {
                this.loadingBarService.complete();
                this.notificationService.printErrorMessage('Failed to load users. ' + error);
            });
    }

    public hideChildModal(): void {
        this.childModal.hide();
    }
}