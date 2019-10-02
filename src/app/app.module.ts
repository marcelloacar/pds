import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AngularFontAwesomeModule } from 'angular-font-awesome';

import { AppComponent } from './app.component';
import { HeaderComponent } from './header/header.component';
import { MenusComponent } from './menus/menus.component';
import { SearchComponent } from './search/search.component';
import { CadastroComponent } from './cadastro/cadastro.component';
import { RodasComponent } from './produtos/rodas/rodas.component';
import { PneusComponent } from './produtos/pneus/pneus.component';
import { AcessoriosComponent } from './produtos/acessorios/acessorios.component';
import { PostagemComponent } from './postagem/postagem.component';

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    MenusComponent,
    SearchComponent,
    CadastroComponent,
    RodasComponent,
    PneusComponent,
    AcessoriosComponent,
    PostagemComponent
  ],
  imports: [
    BrowserModule,
    AngularFontAwesomeModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
