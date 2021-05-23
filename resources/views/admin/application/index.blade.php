@extends('layouts.admin')

@section('styles')
    <style>
        .links a {
            margin: 10px;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-5">
        <div class="card">
            <div class="card-header">
                Application Settings
            </div>
            <div class="card-body">
                <form action="">
                    @csrf
                    <div class="form-group">
                        <label for="name">Website Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ trans('panel.site_title') }}" readonly disabled>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-7">

        <div class="card">
            <div class="card-header">
                Application Management
            </div>
            <div class="card-body">
                <div class="row links">
                        <a class="btn btn-outline-danger" data-toggle="modal" data-target="#cacheModel" href="#">clear cache</a>
                        <a class="btn btn-outline-danger" href="{{ route('admin.app.audit') }}" onclick="return confirm('Are you sure?')">clear Audit</a>
                        <a class="btn btn-outline-danger" href="{{ route('admin.app.media') }}" onclick="return confirm('Are you sure?')">clear media</a>
                        <a class="btn btn-outline-danger" href="{{ route('admin.app.token') }}" onclick="return confirm('Are you sure?')">clear token</a>
                        <a class="btn btn-outline-danger" href="{{ route('admin.app.all-cache') }}" onclick="return confirm('Are you sure?')">clear all-cache</a>
                        <div class="modal fade" id="cacheModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Clear Cache</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <a href="{{ route('admin.app.cache') }}?tags=temp" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">Clean Temp Cache</a>
                                  <a href="{{ route('admin.app.cache') }}?tags=prod" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">Clean Prod Cache</a>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="row links">
                        <a href="{{ route('admin.app.sys-link') }}" onclick="return confirm('Are you sure?')" class="btn btn-outline-success">Link Storage</a>
                        <a href="{{ route('admin.app.scraper') }}" onclick="return confirm('Are you sure?')" class="btn btn-outline-success">Start Scrapper</a>
                        <a href="{{ route('admin.app.covid-scrapper') }}" onclick="return confirm('Are you sure?')" class="btn btn-outline-success">Start covid-scrapper</a>
                        <a href="{{ route('admin.app.covid-commands') }}" data-toggle="modal" data-target="#scraperCommands" class="btn btn-outline-success">Start covid-commands</a>
                        <div class="modal fade" id="scraperCommands" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Run Covid Scraper Commands</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <a href="{{ route('admin.app.covid-commands') }}?command=historical" onclick="return confirm('Are you sure?')" class="btn btn-outline-success">Start historical Scraper</a>
                                    <a href="{{ route('admin.app.covid-commands') }}?command=worldometers" onclick="return confirm('Are you sure?')" class="btn btn-outline-success">Start worldometers Scraper</a>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                    <div class="row links">

                        <a href="{{ route('admin.app.down') }}" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger">Site down</a>
                        <a href="{{ route('admin.app.up') }}" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger">Site Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
