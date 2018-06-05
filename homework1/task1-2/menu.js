function Container() {
  this.tagName = 'div';
  this.className = 'container';
  this.id = 'container';
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
    if (item instanceof MenuItem || item instanceof SuperMenu) {
      menu.appendChild(item.render());
    }
  });
  return menu;
};

function SuperMenu(className, id, href, title, items, superMenuPointClass) {
  Menu.call(this);
  this.href = href;
  this.title = title;
  this.items = items;
  this.className = className;
  this.superMenuPointClass = superMenuPointClass;
  this.id = id;
}

SuperMenu.prototype = Object.create(Container.prototype);
SuperMenu.prototype.render = function() {

  var menuPoint = new MenuItem(this.href, this.title, this.superMenuPointClass);
  var menuPointElem = menuPoint.render();

  var superMenu = document.createElement(this.tagName);
  superMenu.className = this.className;
  superMenu.id = this.id;

  this.items.forEach(function(item) {
    if (item instanceof MenuItem) {
      superMenu.appendChild(item.render());
    }
  });

  menuPointElem.appendChild(superMenu);

  return menuPointElem;
};

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
  link.href = this.href;
  link.textContent = this.title;

  li.appendChild(link);

  return li;
};

window.onload = function() {
  var superMenuItems = [
    new MenuItem('https://geekbrains.ru', '1', 'super-menu-point'),
    new MenuItem('https://geekbrains.ru', '2', 'super-menu-point'),
    new MenuItem('https://geekbrains.ru', '3', 'super-menu-point')
  ];

  var items = [
    new MenuItem('https://geekbrains.ru', 'Home', 'menu-point'),
    new SuperMenu('super-menu', 'super-menu', 'https://geekbrains.ru', 'News', superMenuItems, 'menu-point'),
    new MenuItem('https://geekbrains.ru', 'Blog', 'menu-point')
  ];

  var menu = new Menu('menu', 'menu', items);

  document.body.appendChild(menu.render());

};

