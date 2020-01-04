<?php
namespace App\Http\Controllers\Admin\Common;

use Maatwebsite\Excel\Facades\Excel;

use Maatwebsite\Excel\HeadingRowImport;

// use NotifyUserOfCompletedExport;
// use NotifyUserOfCompletedImport;

class DownloadController extends Controller {
    public function index($type='abc') {
        $path = '';

        switch ( $type ) {
            case 'picture':
            break;
            default:
            break;
        }

        if ( $path ) {
            return response()->download($path);
        } else {
            return back();
        }
    }

    public function export($category, $slug) { // 下载到客户端
        $export_class = '\\App\\Exports\\'.$category.'\\'.$slug.'Export';

    	// return Excel::download(new $export_class, $category.'-'.$slug.'.xlsx');
        // return new $export_class;
        return new $export_class(2, 2018);
        // return (new $export_class)->download($category.'-'.$slug.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // return (new $export_class)->download($category.'-'.$slug.'.csv', \Maatwebsite\Excel\Excel::CSV);
        // return (new $export_class)->download($category.'-'.$slug.'.csv', \Maatwebsite\Excel\Excel::CSV, [
        //     'Content-Type' => 'text/csv',
        // ]);
        // return (new $export_class)->download($category.'-'.$slug.'.tsv', \Maatwebsite\Excel\Excel::TSV);
        // return (new $export_class)->download($category.'-'.$slug.'.ods', \Maatwebsite\Excel\Excel::ODS);
        // return (new $export_class)->download($category.'-'.$slug.'.xls', \Maatwebsite\Excel\Excel::XLS);
        // return (new $export_class)->download($category.'-'.$slug.'.html', \Maatwebsite\Excel\Excel::HTML);
        // return (new $export_class)->download($category.'-'.$slug.'.pdf', \Maatwebsite\Excel\Excel::MPDF);
        // return (new $export_class)->download($category.'-'.$slug.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        // return (new $export_class)->download($category.'-'.$slug.'.pdf', \Maatwebsite\Excel\Excel::TCPDF);

        // return \App\Models\Report\Introduce::all()->downloadExcel($category.'-'.$slug.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function store($category, $slug) { // 保存到服务器
        $export_class = '\\App\\Exports\\'.$category.'\\'.$slug.'Export';

        $title = $category.'-'.$slug.'-'.md5(date('YmdHis').rand(0, 99999)).'.xlsx';

        // $result = Excel::store(new $export_class, $title);
        // $result = Excel::store($export_class, $title, 's3'); // 保存到指定引擎
        // $result = Excel::store($export_class, $title, 's3', Excel::XLSX); // 保存格式
        // $result = Excel::store($export_class, $title, 's3', null, [ // 属性
        //     'visibility' => 'private',
        // ]);
        // $result = Excel::store($export_class, $title, 's3', null, 'private'); // 属性

        // $result = \App\Models\Report\Introduce::all()->storeExcel($category.'-'.$slug.'.xlsx', null, \Maatwebsite\Excel\Excel::XLSX);

        // (new $export_class)->queue($title);
        // (new $export_class)->queue($title)->chain([
        //     // new NotifyUserOfCompletedExport(request()->user()),
        // ]);
        // (new $export_class)->queue($title)->allOnQueue('exports');
        // return back()->withSuccess('Export started!');

        // $content = Excel::raw(new $export_class, \Maatwebsite\Excel\Excel::XLSX); // 输出内容

        // $return = [
        //     'result' => $result ? '1' : '0',
        // ];

        // if ( $result ) {
        //     $return['path'] = '/strorage/'.$title;
        // } else {
        //     $return['message'] = 'error';
        // }

        // return $return;
    }
    public function import($category, $slug) { // 导入
        $import_class = '\\App\\Imports\\'.$category.'\\'.$slug.'Import';

        $file = 'users.xlsx';

        Excel::import(new $import_class, storage_path($file));
        Excel::import(new $import_class, storage_path($file), 's3');
        Excel::import(new $import_class, storage_path($file), 's3', \Maatwebsite\Excel\Excel::XLSX);
        Excel::import(new $import_class, request()->file('file'));

        $array = Excel::toArray(new $import_class, storage_path($file));
        $collection = Excel::toCollection(new $import_class, storage_path($file));

        (new $import_class)->import(storage_path('users.xlsx'), null, \Maatwebsite\Excel\Excel::XLSX);
        (new $import_class)->import(storage_path('users.csv'), null, \Maatwebsite\Excel\Excel::CSV);
        (new $import_class)->import(storage_path('users.tsv'), null, \Maatwebsite\Excel\Excel::TSV);
        (new $import_class)->import(storage_path('users.ods'), null, \Maatwebsite\Excel\Excel::ODS);
        (new $import_class)->import(storage_path('users.xls'), null, \Maatwebsite\Excel\Excel::XLS);
        (new $import_class)->import(storage_path('users.slk'), null, \Maatwebsite\Excel\Excel::SLK);
        (new $import_class)->import(storage_path('users.xml'), null, \Maatwebsite\Excel\Excel::XML);
        (new $import_class)->import(storage_path('users.gnumeric'), null, \Maatwebsite\Excel\Excel::GNUMERIC);
        (new $import_class)->import(storage_path('users.html'), null, \Maatwebsite\Excel\Excel::HTML);

        $import = new $import_class();
        $import->onlySheets('Worksheet 1', 'Worksheet 3');
        Excel::import($import, storage_path($file));

        // $headings = (new HeadingRowImport)->toArray(storage_path($file)); // 仅导入头部

        // Excel::queueImport(new $import_class, storage_path($file));
        // (new $import_class)->queue(storage_path($file));

        // (new $import_class)->queue(storage_path($file))->chain([
        //     new NotifyUserOfCompletedImport(request()->user()),
        // ]);

        return redirect('/')->with('success', 'All good!');
    }
}
// http://laravel56.beesoft.org/admin/import/rbac-user
