@php echo "<?php"
@endphp namespace {{ $controllerNamespace }};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brackets\Admin\AdminListing;
use {{ $modelFullName }};

class {{ $controllerBaseName }} extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|array
     */
    public function index(Request $request)
    {
        // TODO add authorization

        // TODO params validation (filter/search/pagination/ordering) - maybe extract as a Request?

        // create and AdminListing instance for a specific model and
        $data = AdminListing::instance({{ $modelBaseName }}::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['{!! implode('\', \'', $columnsToQuery) !!}'],

            // set columns to searchIn
            ['{!! implode('\', \'', $columnsToSearchIn) !!}']
        );

        if ($request->ajax()) {
            return ['data' => $data];
        }

        return view('admin.{{ $modelRouteAndViewName }}.index', ['data' => $data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO add authorization

        return view('admin.{{ $modelRouteAndViewName }}.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO add authorization

        // Validate the request
        $this->validate($request, [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{{ implode('|', (array) $column['rules']) }}',
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columns as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        // Store the {{ $modelBaseName }}
        {{ $modelBaseName }}::create($sanitized);

        return redirect('admin/{{ $modelRouteAndViewName }}')
            ->withSuccess("Created");
    }

    /**
     * Display the specified resource.
     * @param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * @return \Illuminate\Http\Response
     */
    public function show({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * @return \Illuminate\Http\Response
     */
    public function edit({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization

        return view('admin.{{ $modelRouteAndViewName }}.edit', [
            '{{ $modelRouteAndViewName }}' => ${{ $modelVariableName }},
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization

        // Validate the request
        $this->validate($request, [
            @foreach($columns as $column)'{{ $column['name'] }}' => '{{ implode('|', (array) $column['rules']) }}',
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columns as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        // Update changed values {{ $modelBaseName }}
        ${{ $modelVariableName }}->update($sanitized);

        return redirect('admin/{{ $modelRouteAndViewName }}')
            ->withSuccess("Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * @return \Illuminate\Http\Response
     */
    public function destroy({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // TODO add authorization

        ${{ $modelVariableName }}->delete();

        return redirect()->back()
            ->withSuccess("Deleted");
    }

}
