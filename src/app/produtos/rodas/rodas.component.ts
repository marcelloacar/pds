import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'rodas',
  templateUrl: './rodas.component.html',
  styleUrls: ['./rodas.component.css']
})
export class RodasComponent implements OnInit {

  constructor() { }

  ngOnInit() {
  }

  id: number;
  descricao: string;
  preco: number;
  tipo: string;
  marca: string;

}
