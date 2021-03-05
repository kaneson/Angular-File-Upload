import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {FileUploader} from 'ng2-file-upload';
import {Observable} from 'rxjs';
import {HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-file-upload',
  templateUrl: './file-upload.component.html',
  styleUrls: ['./file-upload.component.css']
})
export class FileUploadComponent implements OnInit {

  uploadForm: FormGroup;

  public uploader: FileUploader = new FileUploader({
    isHTML5: true
  });

  title: string = 'File Upload';
  downloadLink = '';
  constructor(private fb: FormBuilder, private http: HttpClient ) { }

  uploadSubmit(){
        for (var i = 0; i < this.uploader.queue.length; i++) {
          let fileItem = this.uploader.queue[i]._file;
          if(fileItem.size > 10000000){
            alert("Each File should be less than 10 MB of size.");
            return;
          }
        }
        for (var j = 0; j < this.uploader.queue.length; j++) {
          let dataForm = new FormData();
          let fileItem = this.uploader.queue[j]._file;
          console.log(fileItem.name);
          dataForm.append('file', fileItem);
          dataForm.append('fileSeq', 'seq'+j);
          dataForm.append( 'dataType', this.uploadForm.controls.type.value);
          dataForm.append( 'source', this.uploadForm.controls.source.value);
          dataForm.append( 'destination', this.uploadForm.controls.destination.value);
          console.log(dataForm);
          this.uploadFile(dataForm).subscribe(data => window.location.href = 'https://sac.rebisconsulting.com/tools/upload/uploads/' + data.link);
        }
        this.uploader.clearQueue();
  }

  uploadFile(data: FormData): Observable<any> {
    // debugger
    return this.http.post<any>('https://sac.rebisconsulting.com/tools/upload/upload.php', data);
  }

  ngOnInit() {
    this.uploadForm = this.fb.group({
      document: [null, null],
      type: 'File',
      source:  ['C9xht1myjk1olookllp4bla6f4', Validators.compose([Validators.required])],
      destination:  ['C3QINITSUXFV5W3VLELZBI1OG', Validators.compose([Validators.required])],
      terms:  [false, Validators.compose([Validators.required])]
    });
  }

}


