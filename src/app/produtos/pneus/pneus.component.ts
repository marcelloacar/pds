import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'pneus',
  templateUrl: './pneus.component.html',
  styleUrls: ['./pneus.component.css']
})
export class PneusComponent implements OnInit {

  constructor() { }

  ngOnInit() {
  }

  id: number;
  descricao: string;
  preco: number;
  tipo: string;
  marca: string;

}
