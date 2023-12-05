import { Component, OnInit } from '@angular/core';
import { UserServiceService } from './user-service.service';
import Swal from 'sweetalert2';
import { AuthServiceService } from '../../auth/auth-service.service';
import { FormBuilder, FormGroup } from '@angular/forms';

@Component({
  selector: 'app-etudiant',
  templateUrl: './etudiant.component.html',
  styleUrls: ['./etudiant.component.css']
})
export class EtudiantComponent implements OnInit {
  fonctionnalitesRp!: boolean;
  fonctionnalitesProf!: boolean;
  fonctionnalitesAttache!: boolean;
  form!: FormGroup
  constructor(private userService: UserServiceService, private authService: AuthServiceService, private fb: FormBuilder) {
    this.form = this.fb.group({
      libelle: [''],
      niveau: [''],
      effectif: [0],
    });
    this.fonctionnalitesRp = this.authService.isRp();
    this.fonctionnalitesProf = this.authService.isProf();
    this.fonctionnalitesAttache = this.authService.isAttache();
  }
  ngOnInit(): void {
    this.all()

  }

  classes!: any[]
  fileToUpload: File | null = null;
  json: any[] = []
  loader: boolean = false;

  all() {
    this.userService.all().subscribe((result: any) => {
      console.log(result.data1);
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



  addStudents() {
    this.loader = true
    this.userService.store(this.json).subscribe({
      next: (result: any) => {
        if (result.data1) {
          console.log(result);
          this.classes = result.data1

          Swal.fire({
            title: 'Succès',
            text: 'Les éleves ont été insérés avec succès.',
            icon: 'success',
            confirmButtonText: 'OK'
          })
        } else if (result.error) {
          console.log(result.error);

          Swal.fire({
            title: 'Erreur',
            text: "Eleve(s) deja inséré(s).",
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      },
      error: (errors: any) => {
        console.log(errors);
        this.loader = false
      },
      complete: () => {
        this.loader = false
        this.json = []
      }
    });

  }

  eleves: any[]=[]
  listeElevesClasses(id: number) {
    this.userService.classeEleves(id).subscribe((result: any) => {
      console.log(result);
      this.eleves = result.data2
    })
  }

  viderJson() {
    this.json = [];
  }
  supprimer(index: number): void {
    // Supprime l'élément à l'index spécifié
    this.json.splice(index, 1);
  }

  addClasse() {
    console.log(this.form.value);
    this.userService.storeClasse(this.form.value).subscribe((result) => {
      console.log(result);
      Swal.fire({
        title: 'Succès',
        text: 'Classe ajoutee avec succès.',
        icon: 'success',
        confirmButtonText: 'OK'
      })
      this.classes.unshift(result)

    })
  }
}
