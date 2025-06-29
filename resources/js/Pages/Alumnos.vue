<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto py-8 px-4">
      <h1 class="text-3xl font-bold mb-8 text-center text-blue-800 tracking-tight">Gestión de Alumnos</h1>

      <!-- Mensajes -->
      <div v-if="mensaje" :class="{'bg-blue-100 text-blue-700': exito, 'bg-red-100 text-red-700': !exito}" class="p-2 mb-6 rounded shadow text-center font-semibold">
        {{ mensaje }}
      </div>

      <!-- Sección: Buscar alumnos -->
      <section class="bg-blue-50 rounded-xl shadow mb-8 p-6 border border-blue-100">
        <h2 class="text-xl font-semibold mb-4 text-blue-700 flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/></svg>
          Buscar alumnos
        </h2>
        <div class="flex flex-wrap gap-2 items-end">
          <div>
            <label class="block text-xs text-gray-500 mb-1">Nombre</label>
            <input v-model="filtros.nombre" type="text" placeholder="Nombre" class="border border-blue-200 rounded px-2 py-1 w-36 bg-white" />
          </div>
          <div>
            <label class="block text-xs text-gray-500 mb-1">Curso</label>
            <select v-model="filtros.curso" class="border border-blue-200 rounded px-2 py-1 w-40 bg-white">
              <option value="">Todos los cursos</option>
              <option v-for="curso in cursos" :key="curso.id" :value="curso.id">{{ curso.nombre_curso }}</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-gray-500 mb-1">Turno</label>
            <select v-model="filtros.turno" class="border border-blue-200 rounded px-2 py-1 w-40 bg-white">
              <option value="">Todos los turnos</option>
              <option v-for="turno in turnos" :key="turno">{{ turno }}</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-gray-500 mb-1">Desde</label>
            <input v-model="filtros.fecha_inicio" type="date" class="border border-blue-200 rounded px-2 py-1 w-36 bg-white" />
          </div>
          <div>
            <label class="block text-xs text-gray-500 mb-1">Hasta</label>
            <input v-model="filtros.fecha_fin" type="date" class="border border-blue-200 rounded px-2 py-1 w-36 bg-white" />
          </div>
          <button @click="buscarAlumnos" class="bg-blue-400 text-white px-4 py-2 rounded shadow hover:bg-blue-500 transition">Filtrar</button>
          <button @click="limpiarFiltros" class="bg-gray-200 px-4 py-2 rounded shadow hover:bg-gray-300 transition">Limpiar</button>
        </div>
      </section>

      <!-- Botón para mostrar/ocultar formulario de nuevo alumno -->
      <div class="flex justify-end mb-4">
        <button @click="mostrarNuevo = !mostrarNuevo" class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 transition">
          <svg v-if="!mostrarNuevo" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
          {{ mostrarNuevo ? 'Ocultar formulario' : 'Nuevo alumno' }}
        </button>
      </div>

      <!-- Sección: Nuevo alumno (desplegable) -->
      <section v-show="mostrarNuevo" class="bg-white rounded-xl shadow mb-8 p-6 border border-gray-200 transition-all duration-300">
        <h2 class="text-xl font-semibold mb-4 text-blue-600 flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Nuevo alumno
        </h2>
        <form @submit.prevent="guardarAlumno">
          <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[180px]">
              <label class="block text-gray-600 mb-1">Nombre</label>
              <input v-model="form.nombre_alumno" type="text" class="border border-blue-200 rounded w-full px-2 py-1 bg-white" />
              <span v-if="errores.nombre_alumno" class="text-red-500 text-sm">{{ errores.nombre_alumno }}</span>
            </div>
            <div class="flex-1 min-w-[180px]">
              <label class="block text-gray-600 mb-1">Email</label>
              <input v-model="form.email" type="email" class="border border-blue-200 rounded w-full px-2 py-1 bg-white" />
              <span v-if="errores.email" class="text-red-500 text-sm">{{ errores.email }}</span>
            </div>
            <div class="flex-1 min-w-[180px]">
              <label class="block text-gray-600 mb-1">Curso</label>
              <select v-model="form.id_curso" @change="actualizarTurno" class="border border-blue-200 rounded w-full px-2 py-1 bg-white">
                <option value="">Seleccione curso...</option>
                <option v-for="curso in cursos" :key="curso.id" :value="curso.id">{{ curso.nombre_curso }}</option>
              </select>
              <span v-if="errores.id_curso" class="text-red-500 text-sm">{{ errores.id_curso }}</span>
            </div>
            <div class="flex-1 min-w-[140px]">
              <label class="block text-gray-600 mb-1">Turno</label>
              <input type="text" :value="turnoSeleccionado" class="border border-blue-200 rounded w-full px-2 py-1 bg-gray-100" readonly />
            </div>
          </div>
          <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-5 py-2 rounded shadow hover:bg-blue-600 transition">{{ editando ? 'Actualizar' : 'Registrar' }}</button>
            <button v-if="editando" @click.prevent="cancelarEdicion" class="bg-gray-200 px-5 py-2 rounded shadow hover:bg-gray-300 transition">Cancelar</button>
          </div>
        </form>
      </section>

      <!-- Tabla de alumnos -->
      <section class="bg-blue-50 rounded-xl shadow p-6 border border-blue-100">
        <h2 class="text-xl font-semibold mb-4 text-blue-700 flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
          Lista de alumnos
        </h2>
        <div class="overflow-x-auto">
          <table class="min-w-full rounded">
            <thead class="bg-blue-100">
              <tr>
                <th class="px-4 py-2 text-left text-blue-900 cursor-pointer select-none" @click="ordenarPor('nombre_alumno')">
                  Nombre
                  <OrdenIcono :campo="'nombre_alumno'" :orden="orden" :asc="asc" />
                </th>
                <th class="px-4 py-2 text-left text-blue-900 cursor-pointer select-none" @click="ordenarPor('email')">
                  Email
                  <OrdenIcono :campo="'email'" :orden="orden" :asc="asc" />
                </th>
                <th class="px-4 py-2 text-left text-blue-900 cursor-pointer select-none" @click="ordenarPor('curso')">
                  Curso
                  <OrdenIcono :campo="'curso'" :orden="orden" :asc="asc" />
                </th>
                <th class="px-4 py-2 text-left text-blue-900 cursor-pointer select-none" @click="ordenarPor('turno')">
                  Turno
                  <OrdenIcono :campo="'turno'" :orden="orden" :asc="asc" />
                </th>
                <th class="px-4 py-2 text-left text-blue-900 cursor-pointer select-none" @click="ordenarPor('created_at')">
                  Fecha
                  <OrdenIcono :campo="'created_at'" :orden="orden" :asc="asc" />
                </th>
                <th class="px-4 py-2 text-left text-blue-900">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="alumno in alumnosOrdenados" :key="alumno.id" class="hover:bg-blue-100">
                <td class="px-4 py-2">{{ alumno.nombre_alumno }}</td>
                <td class="px-4 py-2">{{ alumno.email }}</td>
                <td class="px-4 py-2">{{ alumno.curso?.nombre_curso }}</td>
                <td class="px-4 py-2">{{ alumno.curso?.turno }}</td>
                <td class="px-4 py-2">{{ new Date(alumno.created_at).toLocaleDateString() }}</td>
                <td class="px-4 py-2 flex gap-2">
                  <button @click="editarAlumno(alumno)" class="text-blue-600 hover:bg-blue-100 rounded-full p-2 transition" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13zm0 0L4 19l5-6z" /></svg>
                  </button>
                  <button @click="eliminarAlumno(alumno.id)" class="text-red-500 hover:bg-red-100 rounded-full p-2 transition" title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                  </button>
                </td>
              </tr>
              <tr v-if="alumnosOrdenados.length === 0">
                <td colspan="6" class="text-center text-gray-500 py-4">No hay alumnos registrados.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import OrdenIcono from '@/Components/OrdenIcono.vue';

const alumnos = ref([]);
const cursos = ref([]);
const turnos = ref([]);
const form = reactive({ nombre_alumno: '', email: '', id_curso: '' });
const errores = reactive({});
const mensaje = ref('');
const exito = ref(true);
const editando = ref(false);
const idEditando = ref(null);
const filtros = reactive({ nombre: '', curso: '', turno: '', fecha_inicio: '', fecha_fin: '' });
const mostrarNuevo = ref(false);
const orden = ref('created_at');
const asc = ref(false);

const turnoSeleccionado = computed(() => {
  const curso = cursos.value.find(c => c.id == form.id_curso);
  return curso ? curso.turno : '';
});

const obtenerTurnos = () => {
  turnos.value = [...new Set(cursos.value.map(c => c.turno))];
};

const obtenerCursos = async () => {
  const res = await axios.get('/api/cursos');
  cursos.value = res.data.cursos;
  obtenerTurnos();
};

const obtenerAlumnos = async () => {
  const res = await axios.get('/api/alumnos', { params: filtros });
  alumnos.value = res.data.alumnos;
};

const buscarAlumnos = () => {
  obtenerAlumnos();
};

const limpiarFiltros = () => {
  filtros.nombre = '';
  filtros.curso = '';
  filtros.turno = '';
  filtros.fecha_inicio = '';
  filtros.fecha_fin = '';
  obtenerAlumnos();
};

const resetForm = () => {
  form.nombre_alumno = '';
  form.email = '';
  form.id_curso = '';
  Object.keys(errores).forEach(k => errores[k] = '');
  editando.value = false;
  idEditando.value = null;
};

const actualizarTurno = () => {
  // No es necesario hacer nada, el campo turnoSeleccionado se actualiza automáticamente
};

const guardarAlumno = async () => {
  Object.keys(errores).forEach(k => errores[k] = '');
  mensaje.value = '';
  exito.value = true;
  // Validación frontend
  if (!form.nombre_alumno) {
    errores.nombre_alumno = 'El nombre no puede quedar vacío';
    exito.value = false;
  }
  if (!form.email) {
    errores.email = 'El email no puede quedar vacío';
    exito.value = false;
  }
  if (!form.id_curso) {
    errores.id_curso = 'Debe seleccionar un curso';
    exito.value = false;
  }
  if (!exito.value) {
    mensaje.value = 'Ningún Campo puede quedar vacío.';
    return;
  }
  try {
    if (editando.value) {
      await axios.put(`/api/alumnos/${idEditando.value}`, {
        nombre_alumno: form.nombre_alumno,
        email: form.email,
        id_curso: form.id_curso
      });
      mensaje.value = 'Alumno actualizado correctamente';
    } else {
      await axios.post('/api/alumnos', {
        nombre_alumno: form.nombre_alumno,
        email: form.email,
        id_curso: form.id_curso
      });
      mensaje.value = 'Alumno registrado correctamente';
    }
    resetForm();
    obtenerAlumnos();
  } catch (e) {
    exito.value = false;
    if (e.response?.data?.errors) {
      Object.assign(errores, e.response.data.errors);
      mensaje.value = 'Corrija los errores del formulario.';
    } else {
      mensaje.value = e.response?.data?.message || 'Error inesperado.';
    }
  }
};

const editarAlumno = (alumno) => {
  form.nombre_alumno = alumno.nombre_alumno;
  form.email = alumno.email;
  form.id_curso = alumno.id_curso;
  editando.value = true;
  idEditando.value = alumno.id;
  Object.keys(errores).forEach(k => errores[k] = '');
  mensaje.value = '';
};

const cancelarEdicion = () => {
  resetForm();
};

const eliminarAlumno = async (id) => {
  if (!confirm('¿Seguro que desea eliminar este alumno?')) return;
  mensaje.value = '';
  exito.value = true;
  try {
    await axios.delete(`/api/alumnos/${id}`);
    mensaje.value = 'Alumno eliminado correctamente';
    obtenerAlumnos();
  } catch (e) {
    exito.value = false;
    mensaje.value = e.response?.data?.message || 'Error al eliminar.';
  }
};

const ordenarPor = (campo) => {
  if (orden.value === campo) {
    asc.value = !asc.value;
  } else {
    orden.value = campo;
    asc.value = true;
  }
};

const alumnosOrdenados = computed(() => {
  let arr = [...alumnos.value];
  return arr.sort((a, b) => {
    let aVal, bVal;
    if (orden.value === 'curso') {
      aVal = a.curso?.nombre_curso || '';
      bVal = b.curso?.nombre_curso || '';
    } else if (orden.value === 'turno') {
      aVal = a.curso?.turno || '';
      bVal = b.curso?.turno || '';
    } else {
      aVal = a[orden.value] || '';
      bVal = b[orden.value] || '';
    }
    if (aVal < bVal) return asc.value ? -1 : 1;
    if (aVal > bVal) return asc.value ? 1 : -1;
    return 0;
  });
});

onMounted(() => {
  obtenerCursos();
  obtenerAlumnos();
});
</script>

<style scoped>
body {
  background: #f3f6fa;
}
</style> 