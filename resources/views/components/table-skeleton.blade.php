@props([
    'rows' => 3,
    "columns" => 5,
])

<div class="table-skeleton">
    <table>
        <thead>
            <tr>
                @for ($i = 0; $i < $columns; $i++)
                    <th><div class="skeleton skeleton-header"></div></th>
                @endfor   
            </tr>
        </thead>

        <tbody>
            @for ($i = 0; $i < $rows; $i++)
                <tr>
                    <td>
                        <div class="skeleton w-lg"></div>
                    </td>

                    <td>
                        <div class="skeleton w-md"></div>
                    </td>

                    <td>
                        <div class="date-wrapper">
                            <div class="skeleton w-lg"></div>
                            <div class="skeleton w-sm"></div>
                        </div>
                    </td>

                    <td>
                        <div class="skeleton w-xs"></div>
                    </td>

                    <td>
                        <div class="skeleton button-skeleton"></div>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>