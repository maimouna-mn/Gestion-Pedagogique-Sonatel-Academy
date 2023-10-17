import { Component, OnInit } from '@angular/core';
import { UserServiceService } from './user-service.service';

@Component({
  selector: 'app-etudiant',
  templateUrl: './etudiant.component.html',
  styleUrls: ['./etudiant.component.css']
})
export class EtudiantComponent implements OnInit {
  constructor(private userService: UserServiceService) { }
  ngOnInit(): void {
    this.all()
  }
  classes!: any[]
  fileToUpload: File | null = null;
  json!: any
  all() {
    this.userService.all().subscribe((result: any) => {
      console.log(result);
      this.classes = result.data1
    })
  }


  handleFileInput(e: any) {
    e.preventDefault();
    let files = (e.target as HTMLInputElement).files;
    if (!files) {
      return;
    }
    let file = files[0]
    let reader = new FileReader()
    reader.readAsText(file)
    reader.onload = () => {
      this.json = this.csvToJson(reader.result)
      console.log(this.json);

    }
  }

  csvToJson(csvData: any, delimiter: string = ','): any[] {
    const lines = csvData.split('\n');
    const result = [];

    const headers = lines[0].split(delimiter);

    for (let i = 1; i < lines.length; i++) {
      const obj: any = {};
      const currentLine = lines[i].split(delimiter);

      for (let j = 0; j < headers.length; j++) {
        obj[(headers[j])] = currentLine[j];
      }

      result.push(obj);
    }

    return result;
  }
  page = 1;
  totalPages = 1;
  previousPage() {
    if (this.page > 1) {
      this.page--;
      // this.index();
    }
  }

  nextPage() {
    if (this.page < this.totalPages) {
      this.page++;
      // this.index();
    }
  }
  


  addStudents(id: number) {
    this.userService.store(this.json, id).subscribe((result) => {
      console.log(result);
    })
  }

}
