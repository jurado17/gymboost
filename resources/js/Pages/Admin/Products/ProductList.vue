<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Plus } from '@element-plus/icons-vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    products: Array
});

const brands = usePage().props.brands;
const categories = usePage().props.categories;
const isAddProduct = ref(false);
const dialogVisible = ref(false);
const editMode = ref(false);

// subir multiples imagenes
const productImages = ref([]);
const dialogImageUrl = ref('');

const handleFileChange = (file) => {
    productImages.value.push(file);
}

const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url
    dialogVisible.value = true
}

const handleRemove = (file) => {
    console.log(file)
}

//product form data
const id = ref('');
const name = ref('');
const price = ref('');
const description = ref('');
const product_images = ref([]);
const brand_id = ref('');
const category_id = ref('');


//metodo para abrir el modal para añadir nuevos productos
const openAddModal = () => {
    resetFormData();
    isAddProduct.value = true;
    dialogVisible.value = true;
    editMode.value = false;
}

//metodo para añadir un producto nuevo
const addProduct = async () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('price', price.value);
    formData.append('description', description.value);
    formData.append('brand_id', brand_id.value);
    formData.append('category_id', category_id.value);

    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw)
    }

    try {
        await router.post('products/store', formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                })
                dialogVisible.value = false;
                resetFormData();
            },

        });
    } catch (error) {
        console.log(error);
    }
}

//metodo para vaciar el modal 
const resetFormData = () => {
    id.value = '';
    name.value = '';
    description.value = '';
    brand_id.value = '';
    category_id.value = '';
    productImages.value = [];

}

//metodo para abrir el modal para editar un producto
const openEditModal = (product) => {

    isAddProduct.value = false;
    dialogVisible.value = true;
    editMode.value = true;

    id.value = product.id;
    name.value = product.name;
    price.value = product.price;
    description.value = product.description;
    brand_id.value = product.brand_id;
    category_id.value = product.category_id;
    product_images.value = product.product_images;
}

//Borrar imagen en el modal editProduct
const deleteImage = async (pimage, index) => {

    try {
        await router.delete('/admin/products/image/' + pimage.id, {
            onSuccess: (page) => {
                product_images.value.splice(index, 1);
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                })
            }
        })
    } catch (error) {
        console.log(error);
    }
}

//Metodo para actualizar un producto
const updateProduct = async () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('price', price.value);

    formData.append('description', description.value);
    formData.append('brand_id', brand_id.value);
    formData.append('category_id', category_id.value);
    formData.append("_method", 'PUT');

    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw)
    }

    try {
        await router.post('products/update/' + id.value, formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                })
                dialogVisible.value = false;
                resetFormData();
            },

        });
    } catch (error) {
        console.log(error);
    }
}

//Borrar Producto
const deleteProduct = (product, index) => {
    Swal.fire({
        title: '¿Estas Seguro?',
        text: 'Esta accion no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'NO',
        confirmButtonText: 'Si, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete('products/delete/' + product.id, {
                    onSuccess: (page) => {
                        deleteProduct(product, index);
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: page.props.flash.success,
                        })
                    }
                })
            } catch (error) {

            }
        }
    });
}

const publishProduct = (product, index) => {
    Swal.fire({
        title: 'Cambiar estado de publicacion?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'NO',
        confirmButtonText: 'Si, cambiar'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.put('products/publish/' + product.id, {
                    onSuccess: (page) => {
                        publishProduct(product, index);
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: page.props.flash.success,
                        })
                    }
                })
            } catch (error) {
                console.log(error);
            }
        }
    });
}

const CategoryColor = (categoryId) => {
    switch (categoryId) {
        case 1:
            return '#FF5341';
        case 2:
            return '#FFA041';
        case 3:
            return '#FFF441';
        case 4:
            return '#A0FF41';
        case 5:
            return '#41FF6C';
        case 6:
            return '#41FFD4';
        case 7:
            return '#41AFFF';
        case 8:
            return '#4153FF';
        case 9:
            return '#9241FF';
        case 10:
            return '#E541FF';
        default:
            return '#FF41B7';
    }
};

const searchValue = ref('');
const selectedBrands = ref([]);
const selectedCategories = ref([]);

const filteredData = computed(() => {
    let products = props.products;

    // Filtrar por valor de búsqueda
    if (searchValue.value) {
        products = products.filter(row => {
            return Object.values(row).some(column => column !== null && column.toString().toLowerCase().includes(searchValue.value.toLowerCase()));
        });
    }

    // Filtrar por marcas seleccionadas
    if (selectedBrands.value.length > 0) {
        products = products.filter(product => selectedBrands.value.includes(product.brand.id));
    }

    // Filtrar por categorías seleccionadas
    if (selectedCategories.value.length > 0) {
        products = products.filter(product => selectedCategories.value.includes(product.category.id));
    }

    return products;
});

const resetFilters = () => {
    searchValue.value = '';
    selectedBrands.value = [];
    selectedCategories.value = [];
};

</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" v-model="searchValue"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search" required="">
                            </div>
                        </form>
                    </div>
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <button type="button" @click="openAddModal"
                            class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Add product
                        </button>

                        <!--Filtros-->
                        <div class="flex items-center space-x-3 w-full md:w-auto">
                            <!--Boton borrar filtros-->
                            <button @click="resetFilters"
                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800">
                                X Eliminar Filtros
                            </button>

                            <!--Filtro marcas-->
                            <button id="filterBrandDropdownButton" data-dropdown-toggle="filterBrandDropdown"
                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                    class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                </svg>
                                Marcas
                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                            <div id="filterBrandDropdown" class="z-10 hidden  w-48 max-h-48 overflow-y-auto p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                <ul class="space-y-2 text-sm" aria-labelledby="filterBrandDropdownButton">
                                    <li v-for="brand in brands" :key="brand.id" class="flex items-center">
                                        <input :id="'brand-' + brand.id" type="checkbox" :value="brand.id" v-model="selectedBrands"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="'brand-' + brand.id" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ brand.name }}</label>
                                    </li>
                                </ul>
                            </div>

                            <!--Filtro categorías-->
                            <button id="filterDropdownButton" data-dropdown-toggle="filterCategoryDropdown"
                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                    class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                </svg>
                                Categorías
                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                            <div id="filterCategoryDropdown" class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                <ul class="space-y-2 text-sm" aria-labelledby="filterCategoryDropdownButton">
                                    <li v-for="category in categories" :key="category.id" class="flex items-center">
                                        <input :id="'category-' + category.id" type="checkbox" :value="category.id" v-model="selectedCategories"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="'category-' + category.id" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ category.name }}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Product name</th>
                                <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Brand</th>
                                <th scope="col" class="px-4 py-3">Published</th>
                                <th scope="col" class="px-4 py-3">Price/KG</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in filteredData" :key="product.id" class="border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white inline-flex">
                                    
                                    <img v-if="product.product_images.length > 0"
                                        :src="`/storage/${product.product_images[0].image}`" :alt="product.imageAlt"
                                        class="h-12 w-12 object-cover object-center" />
                                        
                                    <img v-else
                                        src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                                        :alt="product.imageAlt" class="h-12 w-12 object-cover object-center" />

                                        <a class="flex ml-3 items-center">{{product.name }}</a>
                                </th>
                                <td class="px-4 py-3">

                                    <span :style="{ backgroundColor: CategoryColor(product.category.id) }"
                                        class="text-xs text-black font-medium me-2 px-2.5 py-0.5 rounded-full">{{
                                            product.category.name }}</span>
                                </td>
                                <td class="px-4 py-3">{{ product.brand.name }}</td>
                                <td class="px-4 py-3">
                                    <button @click="publishProduct(product)" v-if="product.published == 1" type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Publicado</button>
                                    <button @click="publishProduct(product)" v-else type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">No
                                        Publicado</button>
                                </td>
                                <td class="px-4 py-3">{{ product.price }}€</td>

                                <td class="px-4 py-3 flex items-center justify-end">
                                    <button id="dropdown-button-{{ product.id }}" :data-dropdown-toggle="product.id"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div :id="product.id"
                                        class="hidden z-30 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="dropdown-button">
                                            <li>
                                                <Link :href="route('admin.productDetail.index', product.id)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Show
                                                </Link>
                                            </li>
                                            <li>
                                                <a href="#" @click="openEditModal(product)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" @click="deleteProduct(product, index)"
                                                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                        </div>
                                    </div>
                                </td>

                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!--MODAL-->
    <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit Product' : 'Add Product'" width="700">
        <form @submit.prevent="editMode ? updateProduct() : addProduct()">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                        Name</label>
                    <input v-model="name" type="text" name="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Type product name" required="">
                </div>
                <div>
                    <label for="brand"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
                    <select id="brand" v-model="brand_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="price"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                    <input type="text" name="price" id="price" v-model="price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="$2999" required="">
                </div>
                <div>
                    <label for="category"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                    <select id="category" v-model="category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name
                            }}</option>

                    </select>
                </div>
        
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Description</label>
                    <textarea id="description" name="description" rows="8" v-model="description"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Descripción completa del producto"></textarea>
                </div>
            </div>

            <!--Añadir Multiples imagenes-->
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 mt-5">
                <div class="sm:col-span-2">
                    <el-upload v-model:file-list="productImages" list-type="picture-card" multiple
                        action="https://run.mocky.io/v3/9d059bf9-4660-45f2-925d-ce80ad6c4d15"
                        :on-preview="handlePictureCardPreview" :on-remove="handleRemove" :on-change="handleFileChange">
                        <el-icon>
                            <Plus />
                        </el-icon>
                    </el-upload>
                </div>
            </div>

            <!--Lista de imagenes por producto seleccionado-->
            <div class="flex flex-nowrap m-4 ">
                <div v-for="(pimage, index) in product_images" :key="pimage.id" class="relative m-2">
                    <img class="w-32 h-auto rounded" :src="`/storage/${pimage.image}`" alt="">
                    <span
                        class="absolute top-0 right-0 transform -translate-y-1/2 w-3.5 h-3.5 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full cursor-default hover:cursor-pointer">
                        <span @click="deleteImage(pimage, index)"
                            class="text-white text-xs font-bold absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2">x</span>
                    </span>
                </div>
            </div>


            <div>
                <button type="submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 mt-4 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Add new product
                </button>
            </div>

        </form>

    </el-dialog>
</template>