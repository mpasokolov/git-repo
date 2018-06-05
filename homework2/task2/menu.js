function Container() {
  this.tagName = 'nav';
  this.className = 'main-nav';
  this.id = 'main-nav';
}

Container.prototype.render = function() {
  var element = document.createElement(this.tagName);
  element.id = this.id;
  element.className = this.className;
  return element;
};
Container.prototype.remove = function () {
  var element = document.getElementById(this.id);
  var parent = element.parentNode;
  parent.removeChild(element);
};

function Menu(className, id, items) {
  Container.call(this);

  this.tagName = 'ul';
  this.className = className;
  this.id = id;
  this.items = items;
}

Menu.prototype = Object.create(Container.prototype);
Menu.prototype.render = function() {
  var menu = document.createElement(this.tagName);
  menu.className = this.className;
  menu.id = this.id;

  this.items.forEach(function(item) {
    if (item instanceof MenuItem || item instanceof SuperMenuItem) {
      menu.appendChild(item.render());
    }
  });
  return menu;
};

function SuperMenu(className, id, items, title) {
  Menu.call(this);
  if (!title) {
    return new Menu(className, id, items);
  }
  this.items = items;
  this.className = className;
  this.id = id;
}

SuperMenu.prototype = Object.create(Menu.prototype);

function MenuItem(href, title, className) {
  Container.call(this);

  this.tagName = 'li';
  this.className = className;
  this.href = href;
  this.title = title;
}

MenuItem.prototype = Object.create(Container.prototype);
MenuItem.prototype.render = function() {
  var li = document.createElement(this.tagName);
  li.className = this.className;

  var link = document.createElement('a');
  link.className = 'main-nav__link';
  link.href = this.href;
  link.textContent = this.title;

  li.appendChild(link);

  return li;
};

function SuperMenuItem(href, title, className, innerClassName, items) {
  MenuItem.call(this);
  this.className = className;
  this.href = href;
  this.title = title;
  this.innerClassName = innerClassName;
  this.items = items;
}

SuperMenuItem.prototype = Object.create(Container.prototype);
SuperMenuItem.prototype.render = function() {

  var menuPoint = new MenuItem(this.href, this.title, this.className);
  var menuPointElem = menuPoint.render();

  var superMenu = document.createElement('ul');
  superMenu.className = this.innerClassName;

  this.items.forEach(function(item) {
    if (item instanceof MenuItem || item instanceof SuperMenuItem) {
      superMenu.appendChild(item.render());
    }
  });

  menuPointElem.appendChild(superMenu);

  return menuPointElem;
};

window.onload = function() {


  var items = [
    new MenuItem('#', 'HOME', 'main-nav__item'),
    new MenuItem('#', 'MAN', 'main-nav__item'),
    new MenuItem('#', 'WOMAN', 'main-nav__item'),
    new MenuItem('#', 'Kids', 'main-nav__item'),
    new MenuItem('#', 'Accoseriese', 'main-nav__item'),
    new MenuItem('#', 'Featured', 'main-nav__item'),
    new MenuItem('#', 'Hot Deals', 'main-nav__item')
  ];

  var container = new Container().render();

  var superMenu = new SuperMenu('main-nav__list', 'main-nav__list', items).render();

  container.appendChild(superMenu);

  document.body.appendChild(container);

};

