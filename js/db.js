// Base de datos simulada de productos
const productos = [
    {
        id: 1,
        nombre: "Desayuno Sorpresa Amor",
        precio: 45000,
        categoria: "Desayunos",
        imagen: "desayuno_sorpresa.png",
        descripcion: "Un hermoso desayuno para empezar el día con mucho amor y energía."
    },
    {
        id: 2,
        nombre: "Caja de Chocolates Premium",
        precio: 25000,
        categoria: "Chocolates",
        imagen: "caja_chocolate.png",
        descripcion: "Selección de los mejores chocolates artesanales."
    },
    {
        id: 3,
        nombre: "Caja de Dulces Variados",
        precio: 15000,
        categoria: "Dulces",
        imagen: "caja_dulces.png",
        descripcion: "Mezcla perfecta de golosinas deliciosas."
    },
    {
        id: 4,
        nombre: "Ramo de Flores Frescas",
        precio: 35000,
        categoria: "Flores",
        imagen: "flores.png",
        descripcion: "Hermoso arreglo floral seleccionado a mano."
    },
    {
        id: 5,
        nombre: "Peluche Oso Tierno",
        precio: 30000,
        categoria: "Peluches",
        imagen: "peluche.png",
        descripcion: "Oso de peluche suave ideal para regalar."
    },

    // 🔥 NUEVOS PRODUCTOS

    {
        id: 6,
        nombre: "Desayuno Cumpleaños",
        precio: 50000,
        categoria: "Desayunos",
        imagen: "desayunocumpleanos.jpg",
        descripcion: "Incluye torta, jugo, frutas y sorpresa especial."
    },
    {
        id: 7,
        nombre: "Chocolate Corazón",
        precio: 20000,
        categoria: "Chocolates",
        imagen: "chocolatecorazon.jpg",
        descripcion: "Caja en forma de corazón con chocolates finos."
    },
    {
        id: 8,
        nombre: "Mega Caja Dulces",
        precio: 28000,
        categoria: "Dulces",
       imagen: "megacajadulces.jpg",
        descripcion: "Caja grande con variedad de dulces importados."
    },
    {
        id: 9,
        nombre: "Ramo Rosas Rojas",
        precio: 40000,
        categoria: "Flores",
        imagen: "producto1.jpg",
        descripcion: "Rosas rojas frescas para ocasiones especiales."
    },
    {
        id: 10,
        nombre: "Peluche Gigante",
        precio: 60000,
        categoria: "Peluches",
        imagen:"peluche_gigante.jpg",
        descripcion: "Peluche grande y suave para sorprender."
    },
    {
        id: 11,
        nombre: "Combo Dulce + Peluche",
        precio: 45000,
        categoria: "Dulces",
       imagen: "combodulcesmaspeluche.jpg",
        descripcion: "Incluye dulces y un peluche adorable."
    },
    {
        id: 12,
        nombre: "Mini Desayuno Sorpresa",
        precio: 30000,
        categoria: "Desayunos",
       imagen: "minidesayuno.jpg",
        descripcion: "Versión pequeña pero muy especial."
    }
];
// Usuario simulado para que podamos probar el Login luegoxd
const usuario = {
    correo: "cliente@tienda.com",
    contrasena: "MTIzNDU2",
    nombre: "Juan Pérez"
};