@props([
    'rows' => 3,
])

<div class="table-skeleton">
    <table>
        <thead>
            <tr>
                <th><div class="skeleton skeleton-header"></div></th>
                <th><div class="skeleton skeleton-header"></div></th>
                <th><div class="skeleton skeleton-header"></div></th>
                <th><div class="skeleton skeleton-header"></div></th>
                <th><div class="skeleton skeleton-header"></div></th>
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