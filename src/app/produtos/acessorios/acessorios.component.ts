import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'acessorios',
  templateUrl: './acessorios.component.html',
  styleUrls: ['./acessorios.component.css']
})
export class AcessoriosComponent implements OnInit {

  constructor() { }

  ngOnInit() {
  }
  id: number;
  descricao: string;
  preco: number;
  tipo: string;
  marca: string;
}
