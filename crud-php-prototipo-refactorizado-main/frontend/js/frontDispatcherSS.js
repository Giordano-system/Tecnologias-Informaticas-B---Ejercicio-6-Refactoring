
// Definir la URL base según el tipo
const API_URL = `../backend/server.php/studentsSubjects`;
const API_URL_STUDENTS = `../backend/server.php/students`;
const API_URL_SUBJECTS = `../backend/server.php/subjects`;

/**
 * Define la URL del backend que se utilizará en todas las llamadas `fetch`.
 */

document.addEventListener('DOMContentLoaded', () => 
{
    /**
     * Espera a que el contenido HTML esté completamente cargado antes de ejecutar el resto del código JavaScript.
     */
    const studentsSubjectForm = document.getElementById('studentsSubjectForm');
    const studentsSubjectsTableBody = document.getElementById('studentsSubjectsTableBody');
    const studentIdInput = document.getElementById('student_id');
    const subjectIdInput = document.getElementById('subject_id');
    const approvedInput = document.getElementById('approved');
    const studentSubjectIdInput = document.getElementById('studentSubjectId');

    // Leer todos los estudiantes al cargar
    fetchStudentsSubjects();
    fetchStudents();
    fetchSubjects();

    async function fetchStudents()
    {
        try 
        {
            const res = await fetch(API_URL_STUDENTS);
            const students = await res.json();

            if (!studentIdInput) {
            console.error("No se encontró el select de estudiantes.");
            return;
        }
            students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = student.fullname;
                studentIdInput.appendChild(option);
            });
        } catch (err) {
            console.error("Error al obtener estudiantes:", err);
        }
    }

    async function fetchSubjects(){
        try 
        {
            const res = await fetch(API_URL_SUBJECTS);
            const subjects = await res.json();

            if (!subjectIdInput) {
                console.error("No se encontró el select de materias.");
                return;
            }
            subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectIdInput.appendChild(option);
            });
        } catch (err) {
            console.error("Error al obtener materias:", err);
        }
    }

    // Formulario: Crear o actualizar estudiante
    studentsSubjectForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // El preventDefault evita que se recarge la pagina al enviar el formulario

        const formData = {
            student_id: studentIdInput.value,
            subject_id: subjectIdInput.value,
            approved: approvedInput.value,
        };

        // Crea un objeto con la informacion introducida

        const id = studentSubjectIdInput.value;
        const method = id ? 'PUT' : 'POST';
        if (id) formData.id = id;

        // Si hay un ID es pq se esta editando, ya que al crear uno nuevo, no se le asigna ID

        try 
        {
            const response = await fetch(API_URL, {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData),
            });

            // Envia los datos al backend

            if (response.ok) {
                studentsSubjectForm.reset();
                studentSubjectIdInput.value = '';
                await fetchStudentsSubjects();
            } else {
                alert("Error al guardar");
            }
        } catch (err) {
            console.error(err);
        }
    });

    // Obtener materias y renderizar tabla
    async function fetchStudentsSubjects() 
    {
        try 
        {
            const res = await fetch(API_URL);
            const studentssubjects = await res.json();

            //Convierte la respuesta JSON a un array de estudiantes

            //Limpiar tabla de forma segura.
            studentsSubjectsTableBody.replaceChildren();
            //acá innerHTML es seguro a XSS porque no hay entrada de usuario
            //igual no lo uso.
            //studentTableBody.innerHTML = "";

            studentssubjects.forEach(studentsubject => {
                const tr = document.createElement('tr');

                const tdfullname = document.createElement('td');
                tdfullname.textContent = studentsubject.fullname;

                const tdsubject = document.createElement('td');
                tdsubject.textContent = studentsubject.name;

                const tdcondicion = document.createElement('td');
                tdcondicion.textContent = studentsubject.approved;

                const tdActions = document.createElement('td');
                const editBtn = document.createElement('button');
                editBtn.textContent = 'Editar';
                editBtn.classList.add('w3-button', 'w3-light-green', 'w3-small', 'w3-margin-right');
                editBtn.onclick = () => {
                    studentIdInput.value = studentsubject.stu_id;
                    subjectIdInput.value = studentsubject.sub_id;
                    approvedInput.value = studentsubject.condicion;
                    studentSubjectIdInput.value = studentsubject.ss_id;
                };

                //Botón para editar con estilos de la w3schools: carga los datos del estudiante al formulario y guarda su id.

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Borrar';
                deleteBtn.classList.add('w3-button', 'w3-red', 'w3-small');
                deleteBtn.onclick = () => deletestudentSubject(studentsubject.ss_id);

                tdActions.appendChild(editBtn);
                tdActions.appendChild(deleteBtn);

                tr.appendChild(tdfullname);
                tr.appendChild(tdsubject);
                tr.appendChild(tdcondicion);
                tr.appendChild(tdActions);
                studentsSubjectsTableBody.appendChild(tr);
            });
        } catch (err) {
            console.error("Error al obtener materias:", err);
        }
    }

    // Eliminar estudiante
    async function deletestudentSubject(id) 
    {
        if (!confirm("¿Seguro que querés borrar este registro?")) return;

        try 
        {
            const response = await fetch(API_URL, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id }),
            });

            if (response.ok) {
                await fetchStudentsSubjects();
            } else {
                alert("Error al borrar");
            }
        } catch (err) {
            console.error(err);
        }
    }
});
