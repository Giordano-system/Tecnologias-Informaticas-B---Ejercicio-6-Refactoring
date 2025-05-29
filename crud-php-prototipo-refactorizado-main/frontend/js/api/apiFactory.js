export function createAPI(moduleName, config = {}) 
{
    const API_URL = config.urlOverride ?? `../../backend/server.php/${moduleName}`;

    async function sendJSON(method, data) 
    {
      try {
        const res = await fetch(API_URL, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        })
        if (!res.ok) throw new Error(await res.text())
        // Verifica si la respuesta tiene un código de estado NO exitoso (cualquier cosa fuera del rango 200–299).
        return await res.json()
        // Si la respuesta es exitosa, convierte la respuesta a JSON y la devuelve.
        }catch (err) {
            console.log(err)        
            const errMessage = JSON.parse(err.message)
            throw errMessage.error
        }
        /**
         * Si ocurre cualquier error en el bloque try (por ejemplo, fetch falla o la respuesta no fue OK), entra al catch.
           Intenta convertir el mensaje de error (err.message) a un objeto JSON.
           Luego, lanza el mensaje de error limpio que venía desde el servidor (usualmente en una estructura como { "error": "mensaje" }).
         */
    }

    return {
        async fetchAll()
        {
            const res = await fetch(API_URL);
            if (!res.ok) throw new Error("No se pudieron obtener los datos");
            return await res.json();
        },
        async create(data)
        {
            return await sendJSON('POST', data);
        },
        async update(data)
        {
            return await sendJSON('PUT', data);
        },
        async remove(id)
        {
            return await sendJSON('DELETE', { id });
        }
    };
}