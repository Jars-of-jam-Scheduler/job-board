<?php
namespace App\Enums;

enum WorkingPlace : string
{
	case FullRemote = 'full_remote';
	case HybdridRemote = 'hybrid_remote';
	case NoRemote = 'no_remote';
}