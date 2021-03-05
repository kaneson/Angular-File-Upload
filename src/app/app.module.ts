import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { FileUploadComponent } from './file-upload/file-upload.component';
import {RouterModule} from "@angular/router";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {FileDropDirective, FileSelectDirective} from "ng2-file-upload";
import {HttpClientModule} from "@angular/common/http";
import {CustomMaterialModule} from "./file-upload/material.module";
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HomepageComponent } from './homepage/homepage.component';
// import { AppRoutingModule } from './app-routing.module';

@NgModule({
  declarations: [
    AppComponent,
    FileUploadComponent,
    FileSelectDirective,
    FileDropDirective,
    HomepageComponent
  ],
  imports: [
    BrowserModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    CustomMaterialModule,
    RouterModule.forRoot([
      {path: '', component: FileUploadComponent},
			{path: 'tools', component: FileUploadComponent},
			{path: 'home', component: HomepageComponent}
    ]/*, {useHash: false}*/),
    BrowserAnimationsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
