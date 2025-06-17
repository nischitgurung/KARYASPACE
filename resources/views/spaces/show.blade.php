<h1>{{ $space->name }}</h1>
<p>{{ $space->description }}</p>

<h2>Projects</h2>
<ul>
    @foreach($space->projects as $project)
        <li>{{ $project->name }}</li>
    @endforeach
</ul>
